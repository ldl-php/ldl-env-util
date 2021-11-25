# ldl-env-utils

Utilities to parse .env files or straight .env strings, with some additional features which will allow you to add
transforms or ignore certain lines, you can also extend it to add custom transforms of your own. 

## Main ENV file concept

A .env file is a file which is used to specify different configurations for different development environments, 
production/staging/development. These files are usually not committed in git and are comprised usually of:

- Comments
- Variables (VAR=VALUE)
- Empty lines

They contain vital settings for your application to work in each environment.

Examples of some useful use cases of .env:

- At my LOCAL dev environment creating a new feature which notifies users through email, sending emails must be disabled.
- At my LOCAL dev environment, I would like some additional debugging information to be added inside each request. 
- At my LOCAL dev environment, the database password is 123456
- At PRODUCTION environment, the database password is <THE REAL complex password here>
- At PRODUCTION environment, no debugging info must be added on each request.

## LDL .env concept

An env file/string can contain the fore mentioned comments, variables, empty lines *plus* certain compiler directives 
which allow us to do special actions / transforms on variables / skip certain lines / detect duplicate variables.

## Parser and compilers

This package contains a set of parsers and compilers, in this section we explain what each one of these do:

### Parsers

Parsers are in charge of interpreting/parsing which kind of line are we dealing with, for example:

is it a comment?
is it a variable ? 
is it an empty line?
is it a directive?
is it an unknown line that we can't parse?

For each different line type, the parser will return an EnvLineCollection containing objects which represent each 
line, following our previous questions:

is it a comment? EnvLineComment
is it a variable ? EnvLineVar
is it an empty line? EnvEmptyLine
is it a directive? EnvCompilerDirective
is it an unknown line that we can't parse? EnvUnknownLine

### Parsing code example (from an array containing strings)

```php

echo "Create Parser Collection\n";

$parserCollection = new EnvLineParserCollection([
    new EnvLineCommentParser(),
    new EnvLineCompilerDirectiveParser(),
    new EnvEmptyLineParser(),
    new EnvLineVarParser()
]);

$parser = new EnvParser();

//Lines to be parsed

$lines = [
    '#COMMENT LINE',
    'App_Admin_URL=http://localhost:8080',
    'MAINTENANCE_MODE=0',
    'Hey whats up?',
    '!LDL-COMPILER START={"ignore": true}',
    'MUST_NOT_BE_SHOWN=1',
    'MUST_NOT_BE_SHOWN=2',
    '!LDL-COMPILER STOP'
];

echo "Lines to be parsed:\n";
echo var_export($lines,true)."\n\n";

echo "Parse lines:\n";
$lines = $parser->parse($lines);

foreach($lines as $line){
    dump(sprintf('%s = %s', get_class($line), $line));
}
```

Output of the previous code will be the following:

```text
Parse lines:
^ "LDL\Env\Util\Line\Type\Comment\EnvLineComment = #COMMENT LINE"
^ "LDL\Env\Util\Line\Type\Variable\EnvLineVar = App_Admin_URL=http://localhost:8080"
^ "LDL\Env\Util\Line\Type\Variable\EnvLineVar = MAINTENANCE_MODE=0"
^ "LDL\Env\Util\Line\Type\EnvUnknownLine = UNKNOWN LINE"
^ "LDL\Env\Util\Line\Type\Directive\EnvLineDirective = !LDL-COMPILER START={"ignore": true}"
^ "LDL\Env\Util\Line\Type\Variable\EnvLineVar = MUST_NOT_BE_SHOWN=1"
^ "LDL\Env\Util\Line\Type\Variable\EnvLineVar = MUST_NOT_BE_SHOWN=2"
^ "LDL\Env\Util\Line\Type\Directive\EnvCompilerDirective = !LDL-COMPILER STOP"
```

All different core parsers can be found at the folder:

```text
src/Env/Util/Line/Parser/*
```

*For a complete example see:*

[EnvStringParsingExample](example/EnvStringParsingExample.php)

### EnvFileParser

In most situations you will most likely be parsing files, for this, there's an EnvFileParser class with a parse
method which takes in a set of iterable items, you can pass an array or an object which implements \Traversable,
internally this collection will be transformed into a ReadableFileCollection, if a file is not readable an exception 
will be thrown.

Example:

```php
$parser = new EnvFileParser();

$parser->parse([
    '/path/to/file1/.env',
    '/path/to/file2/.env'
]);

foreach($lines as $line){
    dump(sprintf('%s = %s', get_class($line), $line));
}
```

*For a complete example see:*

[EnvFileParsingExample](example/EnvFileParsingExample.php)

### Compiler

The EnvCompiler class has a compile method which takes in an EnvLineCollection, this collection ideally will be the 
return value of the previously parsed strings/files.

```php
$compiler = new EnvCompiler();

//See parser example above
$parsedLines = $parser->parse();

$compiled = $compiler->compiler($parsedLines);

foreach($compiled as $line){
   echo "$line\n";
}

```

Output:

```text
#COMMENT LINE"
App_Admin_URL=http://localhost:8080"
MAINTENANCE_MODE=0"
```

The EnvCompiler class uses an EnvCompilerDirectiveCollection, each item on this collection must be an instance of 
EnvCompilerDirectiveInterface. The collection can be passed to EnvCompiler as a constructor argument 
(enabling you to add a custom set of compiler directives) or a default containing the core compilers will be created.

### Default core compiler directives:

- EnvSkipEmptyLineCompilerDirective

Skips empty lines 

- EnvVarCaseTransformCompilerDirective

Transforms casing on variables (upper or lower),

- EnvIgnoreLineCompilerDirective

When this directive is used, lines below it will be ignored,

- EnvIgnoreCommentsCompilerDirective

Ignores all comments

- EnvDuplicateVarResolverCompilerDirective

If a variable with a duplicate name is found, an exception can be thrown, or a resolution of which variable to use 
can be specified.

- EnvUnknownLineCompilerDirective

By default, if an unknown line is found, an exception will be thrown, the line can also be discarded. 

Default core compiler directives can be found in:

```text
src/Env/Util/Compiler/Directive
```

### EnvCompiler compile method

The compile method works in the following way:

- When a start EnvLineDirective is found, that directive is applied to the rest of the lines
- If another start directive is found, that directive will take point and the previous directive will be lost
- When a stop EnvLineDirective is found, no directives will be applied.

NOTE: If you have set a master directive in EnvCompiler through the constructor, when finding a stop directive, the
master directive will be used.

### TODO

- Write instructions on how to create your very own parser
- Write instructions on how to create your very own compiler directive
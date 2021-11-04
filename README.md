# ldl-env-utils

Utilities to parse .env files with some additional features which will allow you to ignore certain
lines or to transform casing of variables or to add custom directives.

## Main ENV concept

An .env file is a file comprised usually of:

- Comments
- Variables (VAR=VALUE)
- Empty lines

## LDL .env concept

An env file can contain the fore mentioned comments, variables, empty lines *plus* directives which allow
us to do special actions / transforms on variables or lines.

## LDL ENV concepts

- LDL\Env\Util\Line\Parser\EnvLineParserInterface

Interface which contains a createFromString method, the first argument to this method is usually any kind of string
that can be found inside of an env file.

All different core parsers can be found at the folder:

```
src/Env/Util/Line/Parser/*
```

- LDL\Env\Util\Line\Parser\EnvLineParserCollection

Contains a collection of EnvLineParserInterface which are able to detect which kind of line are we dealing with
the parser collection contains a parse method which will iterate through all parsers and call parse on each one of them.
The first parser that is able to parse a certain line will be used.

- LDL\Env\Util\Line\Collection\EnvLineCollectionInterface

Contains a collection of EnvLineInterface instances, different line types can be found in the following directory:

```
src/Env/Util/Line/Type/*
```






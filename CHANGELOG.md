# LDL Env Util Changelog

All changes to this project are documented in this file.

This project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [vx.x.x] - xxxx-xx-xx

### Added

- feature/1201177059252669 - Refactor env parsing / Add documentation
- feature/1201432034065231 - Add before/after parse before/after compile callable collections
- feature/1201447695082332 - Add env line directive factory
- feature/1201675079001777 - Add new EnvPrefixLengthCompilerDirective
- feature/1201698987280922 - Add EnvLoader::loadFile method

### Changed

- fix/1201967437941086 - When env file contains no lines, skip the file
- fix/1201949094431922 - Add better exceptions to EnvFileParser
- fix/1200732308074785 - Fix collections by adding getChainItems
- fix/1201692512287210 - Add $prefixDirectory parameter to EnvFileParser::parse

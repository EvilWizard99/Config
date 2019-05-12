Evil Wizard Creations Config
=========================

Parse configuration options from multiple source types into a single interactive config object.

Features
--------

* Config object wrapping and default value access support
* Import config with over loading
* YAML config file parsing
* BaSH style conf file parsing
* PHP Array style config file parsing
* JSON config file parsing
* Command Line Interface config parsing
* PSR-4 autoloading compliant structure
* Comprehensive Guides and tutorial
* Easy to use to any framework or even a plain php file

Bugs
--------

* **Command Line Interface Parser** - *Issue with hyphens in switch names 'switch-name' for both flags and value parameters.*


ToDo
--------

- [ ] **Parser** - *Move the check for file existence to parser base.*
- [ ] **AParser** - *Allow for validation of source existence in abstraction.*
- [ ] **Config** - *Update the parser to use the scope functionality of Config.*
- [ ] **Command Line Interface Parser** - *Add ability to load additional configuration via CLI switch.*
- [ ] **YAML Parser** - *Add ability to load additional configuration via callback function.*
- [ ] **JSON Parser** - *Add ability to load additional configuration via callback function.*
- [ ] **Parser** - *Create a INI file config parser.*
- [ ] **Examples** - *Write examples and implementations for parser use cases.*
- [ ] **Tests** - *Write more tests for all parsers.*


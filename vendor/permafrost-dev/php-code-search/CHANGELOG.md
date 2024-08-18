# Changelog

All notable changes to `php-code-search` will be documented in this file.

---

## 1.11.0 - 2023-11-10

- update helper function names to avoid issues when running in a Laravel application, thanks to @Hypnopompia for reporting the issue.

## 1.10.6 - 2023-11-10

- fix warnings in PHP 8.1, closes #31

## 1.10.5 - 2021-08-17

- various bug fixes, code cleanup

## 1.10.4 - 2021-08-17

- fix nullable parameter type bug

## 1.10.3 - 2021-08-17

- fix another bug with resolving static calls

## 1.10.2 - 2021-08-17

- fix bug with resolving static method call names

## 1.10.1 - 2021-08-03

- fix nested array bug in `Arr::matchesAny()`

## 1.10.0 - 2021-07-29

- add support for class definition searches using `classes()`
- internal: significant refactoring

## 1.9.0 - 2021-07-28

- add support for function definition searches using `functions()` 

## 1.8.1 - 2021-07-27

- require `permafrost-dev/code-snippets` v1.2.0+
- update `composer.json` keywords

## 1.8.0 - 2021-07-27

- `static()` supports searching for static property accesses like `SomeClass::$myProperty` or `myProperty`
- major internal refactoring of nodes 

## 1.7.0 - 2021-07-25

- use latest version of `code-snippets`
- implement multi-line highlighting for code snippets

## 1.6.5 - 2021-07-25

- fix typehint issue

## 1.6.4 - 2021-07-25

- use the `permafrost-dev/code-snippets` package

## 1.6.3 - 2021-07-24

- add `getLineNumber` helper method to the `CodeSnippet` class

## 1.6.2 - 2021-07-23

- fix additional issues with node names

## 1.6.1 - 2021-07-22

- fix issue with function call node names

## 1.6.0 - 2021-07-07

- all function call, static method call, method call nodes have an `args` property containing the value node(s) of the parsed arguments.
- assignment nodes now have a `value` property and `value()` method.
- strings and numbers are converted to `StringNode` and `NumberNode` nodes, respectively.
- most values converted to Node classes that implement either `ResultNode`, `ValueNode`, or both.
- operations (addition, concat, etc.) converted to Node classes that implement `OperationNode`.
- fixed several bugs related to non-matches being returned as matches.

## 1.5.3 - 2021-07-07

- fix issues with `Assignment` nodes

## 1.5.2 - 2021-07-07

- fix issues with `Array`, `ArrayItem` and `ArrayDimFetch` nodes

## 1.5.1 - 2021-07-06

- internal refactoring

## 1.5.0 - 2021-07-06

- rename `FunctionCallLocation` to `GenericCodeLocation` and remove the name property

## 1.4.0 - 2021-07-05

- allow searching for static method calls like `MyClass` or `MyClass::someMethod`
- add `ResultNode` class
- add `node` property to `SearchResult` class

## 1.3.2 - 2021-07-05

- minor fix to method searching

## 1.3.1 - 2021-07-05

- minor fix to variable searching

## 1.3.0 - 2021-07-05

- add `methods` method
- add `variables` method

## 1.2.1 - 2021-07-05

- fix function search feature

## 1.2.0 - 2021-07-04

- add `searchCode` method

## 1.1.1 - 2021-07-04

- add `filename` property to `File` class

## 1.1.0 - 2021-07-04

- add `file` property to `SearchResult` class

## 1.0.0 - 2021-07-04

- initial release


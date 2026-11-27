# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.0] - 27 November 2026
### Added
- Admin grid and form for CMS pages built with Loki Admin Components, with store view, exact-match and from/to date filters, mass actions and button actions configured through XML layout
- Field allowlist and plain-text validation for CMS page data saved through the Loki form and grid
- Documentation of the required admin permissions and the AdminGws role scopes that are not supported
- PHP 8.5 support

### Changed
- Grid and form controllers now only accept GET requests
- Now requires magento/module-cms and Loki_AdminComponents 0.9.1 or higher

### Removed
- PHP 8.1 support

### Fixed
- HTML in plain-text fields is now detected even when ext-pcre is missing

### Security
- The Loki CMS page grid and form now require CMS page permissions, because their ACL resources now sit under Magento_Cms::page
- Only allowlisted fields can be edited in the Loki CMS page form and grid
- Saving a CMS page through the repository in the admin now checks the design permission

## [0.0.1] - 12 May 2025
### Added
- Initial release
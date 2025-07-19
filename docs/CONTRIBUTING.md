# Contributing to Asaas SDK for Laravel

Thank you for considering contributing to this project! Your help is greatly appreciated. Please follow the guidelines below to help us maintain a high-quality codebase and efficient workflow.

## How to Contribute

1. **Fork the repository** and create your branch from `main`.
2. **Clone your fork** to your local machine.
3. **Install dependencies** using Composer:
   ```bash
   composer install
   ```
4. **Create a descriptive branch name** (e.g., `feature/add-new-endpoint` or `fix/typo-in-readme`).
5. **Make your changes** and write tests if applicable.
6. **Run the test suite** to ensure nothing is broken:
   ```bash
   ./vendor/bin/pest
   ```
7. **Commit your changes** with clear and concise messages.
8. **Push to your fork** and submit a Pull Request (PR) to the `main` branch.

## Code Style

- Follow [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standards.
- Use meaningful variable and function names.
- Write PHPDoc blocks for public classes and methods.
- Keep methods short and focused.

## Testing

- Write tests for new features and bug fixes.
- Ensure all existing tests pass.
- Use descriptive test names that explain what is being tested.
- Follow the existing test structure using Pest PHP.

## Pull Request Process

- Ensure your PR addresses a single concern.
- Reference related issues in your PR description.
- Ensure all tests pass before submitting.
- Be ready to discuss and update your code if requested by reviewers.
- Make sure your code follows the project's coding standards.

## Development Setup

1. Clone the repository
2. Run `composer install` to install dependencies
3. Copy the configuration file and set up your environment
4. Run the tests to make sure everything works: `./vendor/bin/pest`

## What We're Looking For

- Bug fixes
- New Asaas API endpoint implementations
- Documentation improvements
- Performance optimizations
- Test coverage improvements

## Reporting Issues

If you find a bug or have a feature request, please [open an issue](../../issues) and provide:

- Clear description of the problem
- Steps to reproduce the issue
- Expected vs actual behavior
- Laravel and PHP versions
- Any relevant code samples

## Security

If you discover a security vulnerability, please follow the instructions in the [Security Policy](../../security/policy) and do **not** open a public issue.

## Documentation

- Update documentation when adding new features
- Ensure code examples work and are up to date
- Keep documentation clear and concise

## Community Guidelines

- Be respectful and considerate in all interactions
- Help others when possible
- Follow the project's code of conduct
- Provide constructive feedback in code reviews

## Questions?

If you have questions about contributing, feel free to open an issue with the `question` label.

Thank you for helping make this project better!

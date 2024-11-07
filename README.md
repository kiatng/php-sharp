# PHP Sharp

A PHP wrapper for [lovell/sharp](https://github.com/lovell/sharp), a high performance Node.js image processing library.

Common use cases:
- Resize image
- Convert image format

For more information on Sharp, see the official documentation [here](https://sharp.pixelplumbing.com/).

## Prerequisites

Install [node](https://nodejs.org/) in your system. Installation of node is very simple.

Check if you have node installed:
```bash
node -v
```
The command should display the version number.

## Installation

Install the packages at the root of your PHP project:
```bash
composer require kiatng/php-sharp
npm install sharp
```

This last command will create a `node_modules` directory, `package-lock.json` and `package.json`.

## Requirements

- PHP >= 7.4
- Node.js and npm (for Sharp installation)
- Sharp npm package

## Usage
There is only one static method `run` in the `Sharp` class. It takes two arguments:
- `$io`: Input and output parameters
- `$params`: Parameters for the image processing

Refer to the [Sharp documentation](https://sharp.pixelplumbing.com/) on the specifications of the parameters.

### Examples

Convert SVG to PNG and resize the image:
```php
use KiatNg\Sharp;

$png = Sharp::run(
    [
        'input' => ['is_raw' => true, 'data' => $svg, 'ext' => 'svg'],
        'output' => ['is_raw' => true],
    ],
    [
        'toFormat' => ['format' => 'png'], // Required for raw output
        'resize' => ['width' => 300, 'height' => 200]
    ]
); // Returns a raw PNG binary string
```

`is_raw` is a boolean parameter that indicates if the input is a raw data or a file path.
`$svg` is the raw SVG XML string.
`ext` is the image format: jpg, png, svg of the source data; it's used as the file extension internally.

Example with file paths including filename and extension:
```php
Sharp::run(
    [
        'input' => ['is_raw' => false, 'data' => $svgPath],
        'output' => ['is_raw' => false, 'file' => $pngPath],
    ],
    [
        //'toFormat' => ['format' => 'png'], Not required if $pngPath has .png extension
        'resize' => ['width' => 300, 'height' => 200]
    ]
);
```

Refer to the test cases in `tests/Unit/SharpTest.php` for more examples.

## Contributing

Contributions are welcome! Here's how you can help:

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Install dependencies:
```bash
composer install
npm install
```

4. Make your changes and add your feature
5. Write or update tests for your changes if applicable
6. Run tests to ensure everything works:
```bash
composer test
```

7. Commit your changes (`git commit -m 'Add some amazing feature'`)
8. Push to the branch (`git push origin feature/amazing-feature`)
9. Open a Pull Request

### Code Style
- Follow PSR-12 coding standards
- Add tests for new features
- Update documentation as needed

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.


## Acknowledgement

This project was inspired by [choowx/rasterize-svg](https://github.com/choowx/rasterize-svg).

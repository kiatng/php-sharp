/**
 * php-sharp - A PHP wrapper for sharp js
 *
 * @link        https://github.com/kiatng/php-sharp
 * @copyright   2024 Ng Kiat Siong
 * @license     MIT
 */
const sharp = require('sharp');

(async () => {
    try {
        const arguments = process.argv.slice(2);

        // Input validation
        if (arguments.length < 2) {
            throw new Error('Missing required arguments: file and apis');
        }

        const [file, apisJson] = arguments;

        // Validate JSON apis
        let apis;
        try {
            apis = JSON.parse(apisJson);
        } catch (e) {
            throw new Error('Invalid apis JSON format');
        }

        // Initialize sharp with input file
        let image = sharp(file);

        // Define supported APIs map, see https://sharp.pixelplumbing.com/
        const supportedApis = {
            // Output options
            keepExif: () => image.keepExif(),
            withExif: (params) => image.withExif(params.exif),
            withExifMerge: (params) => image.withExifMerge(params.exif),
            keepIccProfile: () => image.keepIccProfile(),
            withIccProfile: (params) => image.withIccProfile(params.icc, params.options),
            keepMetadata: () => image.keepMetadata(),
            withMetadata: (params) => image.withMetadata(params.options),
            toFormat: (params) => image.toFormat(params.format, params.options),
            timeout: (params) => image.timeout(params.options),
            // Resizing images
            resize: (params) => image.resize(params.width, params.height, params.options),
            extend: (params) => image.extend(params.extend),
            extract: (params) => image.extract(params.options),
            trim: (params) => image.trim(params.options),
            // Compositing images
            composite: (params) => image.composite(params.images),
            // Image operations
            rotate: (params) => image.rotate(params.angle, params.options),
            flip: () => image.flip(),
            flop: () => image.flop(),
            affine: (params) => image.affine(params.matrix, params.options),
            sharpen: (params) => image.sharpen(params.options, params.flat, params.jagged),
            median: (params) => image.median(params.size),
            blur: (params) => image.blur(params.options),
            flatten: (params) => image.flatten(params.options),
            unflatten: () => image.unflatten(),
            gamma: (params) => image.gamma(params.gamma, params.gammaOut),
            negate: (params) => image.negate(params.options),
            normalise: (params) => image.normalise(params.options),
            normalize: (params) => image.normalize(params.options),
            clahe: (params) => image.clahe(params.options),
            convolve: (params) => image.convolve(params.kernel),
            threshold: (params) => image.threshold(params.threshold, params.options),
            boolean: (params) => image.boolean(params.operand, params.operator, params.options),
            linear: (params) => image.linear(params.a, params.b),
            recomb: (params) => image.recomb(params.inputMatrix),
            modulate: (params) => image.modulate(params.options),
            // Color manipulation
            tint: (params) => image.tint(params.tint),
            greyscale: (params) => image.greyscale(params.greyscale),
            grayscale: (params) => image.grayscale(params.grayscale),
            pipelineColourspace: (params) => image.pipelineColourspace(params.colourspace),
            pipelineColorspace: (params) => image.pipelineColorspace(params.colorspace),
            toColourspace: (params) => image.toColourspace(params.colourspace),
            toColorspace: (params) => image.toColorspace(params.colorspace),
            // Channel manipulation
            removeAlpha: () => image.removeAlpha(),
            ensureAlpha: (params) => image.ensureAlpha(params.alpha),
            extractChannel: (params) => image.extractChannel(params.channel),
            joinChannel: (params) => image.joinChannel(params.images, params.options),
            bandbool: (params) => image.bandbool(params.boolOp),
        };

        // Call APIs
        for (const [api, params] of Object.entries(apis)) {
            if (api in supportedApis) {
                image = supportedApis[api](params);
            } else {
                process.stderr.write(`Warning: Unsupported api '${api}' skipped\n`);
            }
        }

        const result = await image.toBuffer();
        process.stdout.write(result);
    } catch (error) {
        console.error('Error processing image:', error.message);
        process.exit(1);
    }
})();

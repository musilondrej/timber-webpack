const path = require('path');
const glob = require('glob');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const PurgecssPlugin = require('purgecss-webpack-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');


const paths = {
	src: path.join(__dirname, 'static'),
	dist: path.join(__dirname, 'assets'), 
	templates: path.join(__dirname, '') 
};

module.exports = (env, argv) => {
	const isProduction = argv.mode === 'production';

	const config = {
		context: paths.src,
		entry: {
			app: ['./js/app.js', './scss/app.scss'],
		},
		output: {
			filename: 'js/[name].js',
			path: paths.dist,
		},
		module: {
			rules: [
				{
					test: /\.(sa|sc|c)ss$/,
					use: [
						MiniCssExtractPlugin.loader,
						'css-loader',
						'sass-loader',
					],
				},
			],
		},
		plugins: [
			new MiniCssExtractPlugin({
				filename: 'css/[name].css',
			}),
		],
		optimization: {
			minimizer: [
				new TerserPlugin(),
				new CssMinimizerPlugin(),
			],
		},
	};

	if (isProduction) {
		config.plugins.push(
			new PurgecssPlugin({
				paths: glob.sync(`${paths.templates}/**/*.twig`, { nodir: true }),
				safelist: {
					greedy: [],
				},
			})
		);
	}

	return config;
};

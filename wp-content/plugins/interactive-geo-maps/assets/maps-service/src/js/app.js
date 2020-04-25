/**
 * Main app file.  Initializes app components.
 */

/**
 * The main app object.
 *
 */
var iMaps = {};

/**
 * Initializes the interactiveMaps app
 *
 */
iMaps.init = function () {
	am4core.ready(function () {
		// prepare data
		var data = iMapsModel.prepareData(iMapsData.data);
		am4core.options.autoSetClassName = true;
		am4core.options.classNamePrefix = "imaps";
		am4core.options.commercialLicense = true;

		//am4core.options.queue = true;

		if (
			typeof iMapsData.options !== "undefined" &&
			typeof iMapsData.options.lazyLoad !== "undefined" &&
			iMapsData.options.lazyLoad === "1"
		) {
			am4core.options.onlyShowOnViewport = true;
		}

		// animated
		if (
			typeof iMapsData.options !== "undefined" &&
			typeof iMapsData.options.animations !== "undefined" &&
			iMapsData.options.animations === "1"
		) {
			am4core.useTheme(am4themes_animated);
		}

		data.forEach(function (data, index) {
			if (index.disabled) {
				return;
			}

			iMapsManager.init(index);
		});

		iMaps.maps = iMapsManager.maps;
	});
};

iMaps.init();

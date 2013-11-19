/*var	rule = function (value) {
	var response = {
		passed: false,
		message: '{thorax.label} must be eq to "a".',
		value: value
	};
	
	if (value === 'a') {
		response.passed = true;
	}
	
	return response;
};

if (PHP) {
	rule(PHP.parameters.value);
} else {
	return rule;
}*/
var rule = function (value) {
	var response = {
		passed: false,
		message: '{thorax.label} must be eq to "a".',
		value: value
	};
	
	if (value === 'a') {
		response.passed = true;
	}
	
	return response;
};

String(rule);
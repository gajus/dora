var	rule = function (value) {
	var response = {
		passed: false,
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
}
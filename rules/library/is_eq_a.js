var	rule = function (userInput) {
	var response;
	
	response = {
		passed: false,
		errorMessage: '{thorax.label} must be eq to "a".',
		userInput: userInput
	};
	
	if (userInput === 'a') {
		response.passed = true;
	}
	
	return response;
};

if (PHP) {
	rule(PHP.parameters.value);
} else {
	rule;
}
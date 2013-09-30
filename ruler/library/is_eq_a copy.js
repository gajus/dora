var	settings,
	rule;

settings = {
	message: '{label} must be eq. to \'a\'.'
};

rule = function (name, label, value, message) {
	var response = {
		passed: false,
		name: name,
		label: label,
		value: value,
		message: message ? message : settings.message
	};
	
	if (value === 'a') {
		response.passed = true;
	}
	
	return response;
};

if (PHP) {
	if (PHP.parameters) {
		rule(PHP.parameters.name, PHP.parameters.label, PHP.parameters.value, PHP.parameters.message);
	} else {
		settings;
	}
} else {
	return rule;
}
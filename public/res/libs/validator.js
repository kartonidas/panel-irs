var Validator = {
	clear : function(obj) {
		var err = obj.closest("DIV").find(".input-error-info");

		if(!err.length && (obj.closest("DIV").hasClass("bootstrap-select") || obj.closest("DIV").hasClass("input-group")))
			err = obj.closest("DIV").parent().closest("DIV").find(".input-error-info");

		obj.removeClass("input-error");
		err.text("");
	},
    clearAll : function(obj) {
        obj.find(".input-error-info").text("");
        obj.find(".input-error").each(function(){
            $(this).removeClass("input-error");
        });
	},
	setError(obj, msg) {
		var err = obj.closest("DIV").find(".input-error-info");

		if(!err.length && (obj.closest("DIV").hasClass("bootstrap-select") || obj.closest("DIV").hasClass("input-group")))
			err = obj.closest("DIV").parent().closest("DIV").find(".input-error-info");

		obj.addClass("input-error");
		err.text(msg);
	},
	required : function(obj, text) {
		this.clear(obj);
		if(text == "") {
			this.setError(obj, "Uzupełnij dane");
			return false;
		}
		return true;
	},
	requiredifnot : function(obj, text, field) {
	    this.clear(obj);
	    fieldValue = $("input[name='" + field + "']").val();
	    if(fieldValue == undefined || fieldValue == "")
	    {
	        if(text == "") {
                this.setError(obj, "Uzupełnij dane");
                return false;
            }
	    }
	    return true;
	},
	nip : function(obj, text) {
		if(text != "") {
            this.clear(obj);

			text = text.replace(/-|\s/g, "");
            var reg = /^[0-9]{10}$/;
            let weight = [6, 5, 7, 2, 3, 4, 5, 6, 7];
            let sum = 0;
            let controlNumber = parseInt(text.substring(9, 10));
            let weightCount = weight.length;
            for (let i = 0; i < weightCount; i++)
                sum += (parseInt(text.substr(i, 1)) * weight[i]);

            if(!(sum % 11 === controlNumber) || text.length != 10 || reg.test(text) == false) {
				this.setError(obj, "Podaj poprawny NIP");
                return false;
            }
		}
		return true;
	},
	pesel : function(obj, text) {
		if(text != "") {
            this.clear(obj);

            let sum = 0;
			let weight = [1, 3, 7, 9, 1, 3, 7, 9, 1, 3];
			let weightCount = weight.length;
			let controlNumber = parseInt(text.substring(10, 11));

            for (let i = 0; i < weightCount; i++)
                sum += weight[i] * parseInt(text.substr(i, 1));

            let calcControlNumber = 10 - sum % 10;
            calcControlNumber = (calcControlNumber == 10) ? 0 : calcControlNumber;

            if (controlNumber != calcControlNumber) {
				this.setError(obj, "Podaj poprawny numer pesel");
                return false;
			}
		}
		return true;
	},
	email : function(obj, text) {
		if(text != "") {
            this.clear(obj);

            var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/; //"
            if(!re.test(text)) {
				this.setError(obj, "Podaj poprawny adres e-mail");
                return false;
            }
		}
		return true;
	},
	password : function(obj, text) {
		if(text != "") {
            this.clear(obj);
            var re = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})");
            if(text.length < 8) {
                this.setError(obj, "Minimalna długość hasła to 8 znaków");
                return false;
            }
            if(!re.test(text)) {
				this.setError(obj, "Hasło powinno zawierać małą i dużą literę, liczbę i znak specjalny");
                return false;
            }
		}
		return true;
	},
	same : function(obj, text, field) {
		if(text != "") {
			this.clear(obj);

			var input = obj.closest("form").find("input[name='" + field + "']");
			if(input != undefined) {
				if(text != input.val()) {
					this.setError(obj, "Podane hasła nie są identyczne");
					return false;
				}
			}
		}
		return true;
	},
    agree : function(obj, text, field) {
        if(text != "") {
			this.clear(obj);

            var hasError = false;
            field = field.split(",");
            for (i in field) {
                if(!$("input[name='agree["+field[i]+"]']").is(":checked"))
                    hasError = true;
            }
            if(hasError) {
                this.setError(obj, "Wymagane jest zaznaczenie wszystkich zgód");
                return false;
            }
		}
		return true;
    },
    min : function(obj, val, min) {
        if(val != "") {
            min = parseInt(min);
            val = parseInt(val);
            if(isNaN(val) || val < min) {
                this.setError(obj, "Minimalna wartość: " + min);
                return false;
            }
        }
        return true;
    },
    max : function(obj, val, max) {
        if(val != "") {
            max = parseInt(max);
            val = parseInt(val);
            if(isNaN(val) || val > max) {
                this.setError(obj, "Maksymalna wartość: " + max);
                return false;
            }
        }
        return true;
    },
    minlength : function(obj, val, min) {
        if(val.length < min) {
            this.setError(obj, "Minimalna długość " + min + " znaków");
            return false;
        }
        return true;
    },
    regex : function(obj, val, regex) {
        if(val != "") {
            var re = new RegExp(regex, "i")
            if(!re.test(val)) {
                this.setError(obj, "Wartość zawiera nieprawidłowe znaki");
                return false;
            }
        }
        return true;
    },
    login : function(obj, val) {
        if(val != "") {
            var re = new RegExp("^[a-z0-9_]+$", "i")
            if(!re.test(val)) {
                this.setError(obj, "Login może składać się jedynie z liter, cyfr oraz znaku _");
                return false;
            }
        }
        return true;
    },
    firmid : function(obj, val) {
        if(val != "") {
            var re = new RegExp("^[a-z0-9_]+$", "i")
            if(!re.test(val)) {
                this.setError(obj, "Identyfikator może składać się jedynie z liter, cyfr oraz znaku _");
                return false;
            }
        }
        return true;
    },
    integer : function(obj, val) {
        if(val != "") {
            if(!Number.isInteger(parseFloat(val))) {
                this.setError(obj, "Tylko wartość liczbowa");
                return false;
            }
        }
        return true;
    },
    phone : function(obj, val) {
        this.clear(obj);
        if(val != "") {
            var reg = new RegExp("^[0-9]{9}$", "i");
            if(!reg.test(val)) {
                this.setError(obj, "Nieprawidłowy format telefonu (tylko 9 cyfr)");
                return false;
            }
        }
        return true;
    },
    zip : function(obj, val) {
        this.clear(obj);
        if(val != "") {
            var reg = new RegExp("^[0-9]{2}-[0-9]{3}$", "i");
            if(!reg.test(val)) {
                this.setError(obj, "Nieprawidłowy kod pocztowy");
                return false;
            }
        }
        return true;
    },
    date : function(obj, val) {
        this.clear(obj);
        if(val != "") {
            var reg = new RegExp("^[0-9]{4}-[0-9]{2}-[0-9]{2}$", "i");
            if(!reg.test(val)) {
                this.setError(obj, "Nieprawidłowa data (YYYY-MM-DD)");
                return false;
            }
        }
        return true;
    },
};

/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 82);
/******/ })
/************************************************************************/
/******/ ({

/***/ 82:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(83);


/***/ }),

/***/ 83:
/***/ (function(module, exports) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Bot = function () {
    function Bot(ID, name, botLevel, board) {
        _classCallCheck(this, Bot);

        this.ID = ID;
        this.botLevel = botLevel;
        this.botMemory = board.slice();
        this.nextCombo = undefined;
    }

    _createClass(Bot, [{
        key: "playFirstPiece",
        value: function playFirstPiece(board) {
            var piece = undefined;
            this.updateMemory(board);
            console.log("Bot Memory", this.botMemory);
            if (this.botLevel == 1) {
                piece = this.playRandom(board);
            } else if (this.botLevel == 4) {
                if (this.nextCombo !== undefined) {
                    piece = this.nextCombo.play1;
                } else {
                    piece = this.playRandom(board);
                }
            }
            return piece;
        }
    }, {
        key: "playSecondPiece",
        value: function playSecondPiece(board) {
            var piece = undefined;
            if (this.botLevel == 1) {
                piece = this.playRandom(board);
            } else if (this.botLevel == 4) {

                if (this.nextCombo !== undefined) {
                    console.log(this.nextCombo);
                    piece = this.nextCombo.play2;
                } else {
                    console.log("Playing Random");
                    piece = this.playRandom(board);
                }
            }
            return piece;
        }
    }, {
        key: "watchGame",
        value: function watchGame(pos, value) {
            this.botMemory[pos] = value;
            this.checkAvailableCombo();
        }
    }, {
        key: "updateMemory",
        value: function updateMemory(board) {
            for (var i = 0; i < this.botMemory.length; i++) {
                if (board[i] == -2 && this.botMemory[i] != -2) {
                    //Peça ja foi combinada tirar da Memoria
                    this.botMemory[i] = -2;
                }
            }
        }
    }]);

    return Bot;
}();

Bot.prototype.getHiddenPiecesIndex = function (board) {
    var arr = [];
    for (var i = 0; i < board.length; i++) {
        if (board[i] == -1) {
            arr.push(i);
        }
    }
    return arr;
};

Bot.prototype.checkAvailableCombo = function () {
    for (var i = 0; i < this.botMemory.length; i++) {
        if (this.botMemory[i] >= 0) {
            for (var k = 0; k < this.botMemory.length; k++) {

                //Se nao for a msm peca & nao for peca virada ou por virar & que sejam iguais
                if (i != k && this.botMemory[i] == this.botMemory[k]) {
                    this.nextCombo = {
                        "play1": i,
                        "play2": k
                    };
                    return;
                }
            }
        }
    }
    this.nextCombo = undefined;
};

Bot.prototype.playRandom = function (board) {
    var hidden = this.getHiddenPiecesIndex(board); //array
    var random = Math.floor(Math.random() * hidden.length) + 0;
    return hidden[random];
};

module.exports = Bot;

/***/ })

/******/ });
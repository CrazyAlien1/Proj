class Bot {
    constructor(ID, name, botLevel, board){
        this.ID = ID;
        this.botLevel = botLevel;
        this.botMemory = board.slice();
        this.nextCombo = undefined;
    }

    playFirstPiece(board){
        let piece = undefined;
        this.updateMemory(board);
        console.log("Bot Memory", this.botMemory);
        if(this.botLevel == 1){
            piece = this.playRandom(board);

        }else if(this.botLevel == 4){
            if(this.nextCombo !== undefined){
                piece = this.nextCombo.play1;
            }else{
                piece = this.playRandom(board);
            }
        }
        return piece;
    }

    playSecondPiece(board){
        let piece = undefined;
        if(this.botLevel == 1){
            piece = this.playRandom(board);
        }else if(this.botLevel == 4){

            if(this.nextCombo !== undefined){
                console.log(this.nextCombo);
                piece = this.nextCombo.play2;
            }else{
                console.log("Playing Random");
                piece = this.playRandom(board);
            }
        }
        return piece;
    }

    watchGame(pos, value){
        this.botMemory[pos] = value;
        this.checkAvailableCombo();
    }

    updateMemory(board){
        for(let i = 0; i < this.botMemory.length; i++){
            if(board[i] == -2 && this.botMemory[i] != -2){  //Peça ja foi combinada tirar da Memoria
                this.botMemory[i] = -2;
            }
        }
    }
}

Bot.prototype.getHiddenPiecesIndex = function(board){
    let arr = [];
    for(let i = 0; i < board.length; i++){
        if(board[i] == -1){
            arr.push(i);
        }
    }
    return arr;
}

Bot.prototype.checkAvailableCombo = function(){
    for(let i = 0; i < this.botMemory.length; i++){
        if(this.botMemory[i] >= 0){
            for(let k = 0; k < this.botMemory.length; k++) {

                //Se nao for a msm peca & nao for peca virada ou por virar & que sejam iguais
                if(i != k && this.botMemory[i] == this.botMemory[k]) {
                    this.nextCombo = {
                        "play1" : i,
                        "play2" : k
                    }
                    return;
                }
            }
        }
    }
    this.nextCombo = undefined;
}

Bot.prototype.playRandom = function(board){
    let hidden = this.getHiddenPiecesIndex(board); //array
    let random = Math.floor(Math.random() * hidden.length) + 0;
    return hidden[random];
}

module.exports = Bot;

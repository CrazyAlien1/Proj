export default function createOfflineGame(ID, name, rows, cols, images){

    let boardSize = validateBoardSize(rows, cols, images.length);

    if(boardSize > 0) {
        return {
            ID: ID,
            name: name,
            cols: cols,
            rows: rows,
            finalBoard: generateBoard(boardSize, images),
            board: Array(boardSize).fill(42),
            winner: undefined,
            gameEnded: false,
        };
    }
    alert('Game size is impossible to create pls try min: 4 or max '+ images.length+ ' and even size!');
}

function validateBoardSize(rows, cols, maxPieces){
    let piecesQuant = -1;
    let quant = rows * cols;
    if(quant >= 4 && quant <= maxPieces){
        if(quant % 2 == 0){
            piecesQuant = quant;
        }
    }

    return piecesQuant;

}

function generateBoard(quant, images){
    let board = [];
    for (let i = 0; i < quant; i++){
        board = images[i];
    }
    return shuffleArray(board);
}

function shuffleArray(a) {
    for (let i = a.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [a[i], a[j]] = [a[j], a[i]];
    }
    return a;
}
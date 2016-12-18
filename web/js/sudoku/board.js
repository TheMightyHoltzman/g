/**
 * Created by heiko on 17/12/16.
 */

function Board(cells, aSize) {

    this.cells   = cells;
    this.current = null;
    this.size    = aSize;
    this.solved  = false;
    this.stack   = [];

    this.setup = function(cells) {
        for(var i = 0; i < this.size; i++) {
            for(var j = 0; j < this.size; j++) {
                cells[i][j] = new Cell(i, j, cells[i][j]);
            }
        }
        this.current = cells[0][0];
        return cells;
    };

    this.isSolved = function () {
        if (!this.solved) {
            for (var i = 0; i < this.size; i++) {
                for(var j = 0; j<this.size; j++) {
                    if (!this.getCell(i, j).solved) {
                        return false;
                    }
                }
            }
        }
        this.solved = true;
        return true;
    };

    this.show = function() {
        for (var i = 0; i < this.size; i++) {
            for(var j = 0; j < this.size; j++) {
                var val = this.getCell(i, j).val;
                stroke('black');
                textSize(20);
                fill('white');
                if (this.getCell(i,j).solved) {
                    fill('yellow');
                }
                if (this.current.row == i && this.current.col == j) {
                    fill('green');
                }
                rect(i*scl, j*scl, scl, scl);

                if (val != null) {
                    fill('black');
                    text(val, i*scl + 25, j*scl + 35);
                }
            }
        }
    };

    this.solve = function() {
        this.solveCell(this.current.row, this.current.col);
        if(this.current.col + 1 >= this.size && this.current.row + 1 >= this.size ) {
            this.current.col = 0;
            this.current.row = 0;
        }
        else {
            if (this.current.col + 1 >= this.size ) {
                this.current.col = 0;
                this.current.row += 1;
            }
            else {
                this.current.col +=1;
            }
        }
    };

    this.solveCell = function(col, row) {
        if (this.cells[col][row].val !== null) {
            return true;
        }
        this.clearPossibilities(col,row);
        if (this.getCell(col, row).possibilities.length == 1) {
            this.cells[col][row].val    = this.getCell(col, row).possibilities[0];
            this.cells[col][row].solved = true;
            return true;
        }
        return false;
    };

    this.clearPossibilities = function(col, row) {
        this.rowPossibilities(col, row);
        this.colPossibilities(col, row);
        this.squarePossibilities(col, row);
    };

    this.colPossibilities = function(col, row) {
        for(var i = 0; i < this.size; i++) {
            if(this.cells[i][row].val!=null) {
                var cell = this.cells[col][row];
                cell.removePossibility(this.getCell(i, row).val);
            }
        }
    };

    this.rowPossibilities = function(col, row) {
        for(var i = 0; i<this.size; i++) {
            if(this.cells[col][i].val!=null) {
                var cell = this.cells[col][row];
                cell.removePossibility(this.getCell(col, i).val);
            }
        }
    };

    this.squarePossibilities = function(col, row) {
        var cell = this.cells[col][row];

        var startingRow = Math.floor(row/3)*3;
        var startingCol = Math.floor(col/3)*3;

        for(var i = 0; i<3; i++) {
            for(var j = 0; j<3; j++) {
                if(this.cells[startingCol+i][startingRow+j].val != null) {
                    this.cells[col][row].removePossibility(this.getCell(startingCol+i, startingRow+j).val);
                }
            }
        }
    };

    this.getCell = function(col,row) {
        return this.cells[col][row];
    };
}
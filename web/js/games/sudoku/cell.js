function Cell(col, row, val)
{
    this.row = row;
    this.col = col;
    this.val = val;
    this.possibilities = val !== null ? [] : [1,2,3,4,5,6,7,8,9];
    this.solved = false;

    this.removePossibility = function(val) {
        var index = this.possibilities.indexOf(val);
        if (index > -1) {
            this.possibilities.splice(index, 1);
        }
    };

    this.determineSquare = function() {
        return Math.ceil(this.row/3) + Math.ceil(this.col);
    };
}

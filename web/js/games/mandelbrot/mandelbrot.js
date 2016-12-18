function Mandelbrot()
{
    this.iteration = 100;
    this.constA    = 1;
    this.constB    = 1;
    this.threshold = 100;

    this.isIn = function(a, b) {
        var aa = a;
        var bb = b;
        for (var i = 0; i < this.iteration; i++) {
            aa = a*a - b*b + this.constA;
            bb = 2*a*b + this.constB;
        }
        if (Math.abs(aa) + Math.abs(bb) < 100) {
            return true;
        }
        else {
            return false;
        }
    }
}

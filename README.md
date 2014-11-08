nymph-sudoku
============

A Sudoku game, using Angular for UI and Nymph to save games.

How I Built It
--------------

I started off by building a board generating function on the game class to build
a completed Sudoku board. I wrote the test.php file to test this function and
visualize its output.

It seems my first attempt made boards that had missing squares that were
impossible to fill. I tried to remedy it by redoing the row if it encounters
one, but that is taking way too long.

I solved that problem by calculating affinity values based on the values of
the previous row in the square to the left. This effectively rotates values
between squares, if it can. After that alteration, the function is averaging a
little under half a second to generate a completed board.

It seems that ever so rarely, an attempt to make a board will result in an
unsolvable row. I solved that by keeping a row attempt counter. If it gets too
high, I just give up and restart.

To make the board, based on difficulty, the game removes some random squares,
then calculates the number of possible options each remaining square has. If the
game is set to hard, it will remove the squares with the most options. If it's
set to easy, it will remove the squares with the least. It removes up to 5 at
once, then recalculates the possible options.

On hard difficulty, I seem to be getting a lot of clumping of the squares left
over. I'm going to try remedying this by removing more random squares.

I ran into a problem of not being able to tell if a square was a preset or not.
I'm trying to remedy it by making each square an array with 'preset' and 'value'
keys, but that's making the makeItFun step take upwards of 6 seconds. :P

I solved it by just saving the empty board after it's calculated.

I didn't realize that my affinity fix gave the board a predictable pattern. I
fixed that by swapping around the affinities.
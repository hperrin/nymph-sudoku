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
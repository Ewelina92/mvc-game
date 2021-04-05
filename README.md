# Game 21

This is a version of the dice game 21 made for the course mvc within the web programming program at BTH 2021.

## Rules

The player first gets to choose if they want to use one or two dice, and also if they would like to bet some
(made up) bitcoin during the current round. The player then rolls the dice with the goal to get 21 or as close
as possible to 21 (but not over), and then it's the computers turn. The player can stop the rolls earlier than
21 if they want. If the player is above 21, they lose and the computer wins. If they get 21 they win only if the
computer gets above 21, since the computer wins when the result is qual. If they stop earlier, and the computer get
above 21, they win.


### About the code
The code style is object oriented, with four classes:
* Dice (handles amount of faces, rolls and gets the last rolls value)
* GraphicDice (inherits from Dice, can also show a graphic representation of the rolls)
* DiceHand (handles how many dices to roll and the total sum of all the rolls)
* Game (the front controller, controlling the game)

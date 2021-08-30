# PHP Chat Block
A dialogue chat block display component for php project.
A UI component for conversational novel community.
Date: 30 Aug, 2021
Author: Tang Hoong

# Screenshot

![](sample-02.png "sample")

# Description
Just dump the string format which exactly same as sample.txt then ChatBlock will render them into perfect and nice chat blocks.

# How
Follow the format from sample.txt:
```
Snake: https://i.imgur.com/FEiFVeO.png
---
Narator: Once upon a time...
Snake: Wanna eat apple 苹果 🍎🍎🍎?
```
then
```
include 'ChatBlock.php';
$lines = file_get_contents('./chapter.1.txt');
$cb = new ChatBlock($lines);
echo $cb->read();
```
become

![](sample-01.png "sample")

# Resources refer from:

[Codepen IMessage css](https://codepen.io/AllThingsSmitty/pen/jommGQ?editors=1000)
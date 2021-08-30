# PHP Chat UI
PHP Class for simulate conversation dialog.

A UI component for conversational novel community.

Author: Tang Hoong

# Screenshot

![](sample-02.png "sample")

# Description
Just dump the string format which same as sample.txt then ChatUIClass will render them perfect and nice.

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
include 'ChatUIClass.php';
$sample = file_get_contents('./sample.txt');
$dialogue = new ChatUI($sample);
echo $dialogue->read();
```
become

![](sample-01.png "sample")

# Resources refer from:

[Codepen IMessage css](https://codepen.io/AllThingsSmitty/pen/jommGQ?editors=1000)
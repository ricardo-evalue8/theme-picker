# themepicker
 Change theme by clicking on the select theme button.
![Bolt screenshot](https://github.com/ricardo-evalue8/theme-picker/blob/master/assets/screenshot1.png?raw=true)

Extension version
```
v1.0.0 - alpha
```

## Setup

1. Install extension.
1. Visit `/bolt/theme-picker`. And choose your bolt theme.

## Requirements

To let this Extension work 100% you need to add 2 permissions
```
select_theme: [ admin ]
upload_theme: [ developer ]
```

### Plugins

Fils required per theme

| File | location | Info |
| ------ | ------ | ------ |
| theme | [theme/*themename*/theme.png] | Theme screenshot |
| text | [theme/*themename*/text.txt] | Theme info |
| state | [theme/*themename*/state.txt] | Theme enabled/disabled |
| screenshot1 | [theme/*themename*/screenshots/screenshot1.png] | Theme screenshots |

Note: You are able to add more screenshots to the screenshot folder.

## Changelog

* v1.0.0 - Added Upload theme, Enable and Disable theme 
* alpha v1.0.0 - Change theme when click `Select theme`

## TODO
* Enable/Disable theme as developer.
* Upload new themes as Rar/Zip file.

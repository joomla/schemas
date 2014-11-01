joomla-xsd
==========

Schema Validation for Joomla 1.6/1.7/2.5/3.0/3.1 Extensions

Without them, Joomla accept any entry in manifest xml and never complains about

* Mistyping, like a valid xml but that the Joomla installer do not understand or only partially,
* Wrong constructs, xml tag child misplaced,
* Invalid data type, like a path not being a valid path, an expected integer being a text and so on…
Joomla just silently die during install or install only partially extensions. These days are over as developers with any
decent IDE will be able to validate while typing and enjoy auto completion.

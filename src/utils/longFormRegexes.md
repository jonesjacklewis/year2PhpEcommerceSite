# The Long Form version of Regex

## Postcode Regex

Source: [Here](https://en.wikipedia.org/wiki/Postcodes_in_the_United_Kingdom#Validation)

```php
$postcodeRegex = '/^(([A-Z]{1,2}[0-9][A-Z0-9]?|ASCN|STHL|TDCU|BBND|[BFS]IQQ|PCRN|TKCA) ?[0-9][A-Z]{2}|BFPO ?[0-9]{1,4}|(KY[0-9]|MSR|VG|AI)[ -]?[0-9]{4}|[A-Z]{2} ?[0-9]{2}|GE ?CX|GIR ?0A{2}|SAN ?TA1)$/';
```

## Telephone 1

Source: [Here](https://regexlib.com/(X(1)A(RlaBBQkpHAZZrWjixZSuklIizO9fMdBX0L82JJ4s8mwwcfEQ7PPNgkEd1W09BKTPbPuT5qcaswHYHPJFGsC4637lWfJLcCcGMMFTBuTlbkPxp0poduY5bCdNMJt5EqT_w9NQRcYIPFTaF4NvqpOPe1WlHH1wIHvAY8FjmWXZn8yJAfeOho22uZsl9jC7LrvZ0))/UserPatterns.aspx?authorid=d95177b0-6014-4e73-a959-73f1663ae814&AspxAutoDetectCookieSupport=1#:~:text=%5E((%5C(%3F0%5Cd%7B4%7D%5C)%3F%5Cs%3F%5Cd%7B3%7D%5Cs%3F%5Cd%7B3%7D)%7C(%5C(%3F0%5Cd%7B3%7D%5C)%3F%5Cs%3F%5Cd%7B3%7D%5Cs%3F%5Cd%7B4%7D)%7C(%5C(%3F0%5Cd%7B2%7D%5C)%3F%5Cs%3F%5Cd%7B4%7D%5Cs%3F%5Cd%7B4%7D))(%5Cs%3F%5C%23(%5Cd%7B4%7D%7C%5Cd%7B3%7D))%3F%24)

```php
$pattern = '/^((\(?0\d{4}\)?\s?\d{3}\s?\d{3})|(\(?0\d{3}\)?\s?\d{3}\s?\d{4})|(\(?0\d{2}\)?\s?\d{4}\s?\d{4}))(\s?\#(\d{4}|\d{3}))?$/';
```

## Telephone 2

Source: [Here](https://regexlib.com/(X(1)A(RlaBBQkpHAZZrWjixZSuklIizO9fMdBX0L82JJ4s8mwwcfEQ7PPNgkEd1W09BKTPbPuT5qcaswHYHPJFGsC4637lWfJLcCcGMMFTBuTlbkPxp0poduY5bCdNMJt5EqT_w9NQRcYIPFTaF4NvqpOPe1WlHH1wIHvAY8FjmWXZn8yJAfeOho22uZsl9jC7LrvZ0))/UserPatterns.aspx?authorid=d95177b0-6014-4e73-a959-73f1663ae814&AspxAutoDetectCookieSupport=1#:~:text=%5E(%5C%2B44%5Cs%3F7%5Cd%7B3%7D%7C%5C(%3F07%5Cd%7B3%7D%5C)%3F)%5Cs%3F%5Cd%7B3%7D%5Cs%3F%5Cd%7B3%7D%24)

```php
$pattern = '/^(\+44\s?7\d{3}|\(?07\d{3}\)?)\s?\d{3}\s?\d{3}$/';
```
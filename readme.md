# form2pdf

This project was made with :heart: ​to Alê.

The main objective is to take the existing process of transforming form into PDF esier.

### Requirements

It is needed you to have [composer](https://getcomposer.org/) to install the required [mpdf](https://github.com/mpdf/mpdf) package (a PHP Library to generate PDF files).

### Use

1. First of all, install mpdf package by running the command:

   ```
   composer install
   ```

2. Once you had the package installed, you can open your server to see the index.html page and select your template requested;

3. Fill the form fields with your answers, then, click the submit button;

4. You will see the page of your final PDF with your template and respective answers rendered.

### Creating a new template

To create a new template, just create a folder with the name of your template on the folder `templates`. Inside this folder, you must obtain the files `index.json` (with your configurations), and `content.html` (you can provide the `header.html` and `footer.html` files too).

Remember to insert the name of the template in the `settings.json` in `templates` array.

In your `index.json`, should contain a json with this structure:

```json
{
  "title": "Example Title",
  "description": "This is a description",
  "form": []
}
```

You can add the `output` attribute to specify the filename (you can user variables from the answors of your form by using double curly braces "{{variable}}") if you want to save it (if yes, it will be generated in `output/template_name/filename.pdf`).

In your `form` array, you put the fields of you form. Example:

```json
{
  "type": "field type",
  "name": "Field name"
}
```

Possible field types are: `text`, `number`, `radio`, `checkbox`.

#### Type peculiarities

If the field type is `radio` and `checkbox`, you should provide the `options` attribute, an array of strings.

If the field type is `radio`, you can use the attribute `"other": true` to allow the responder of the form select "other" option and insert a value.
# A Little More Custom

👋 Hey Craft friends! Thanks for checking out my talk, [“A Little More Custom”](https://craftcms.com/events/dot-all-2023/sessions/a-little-more-custom), which I gave at [Dot All 2023](https://craftcms.com/events/dot-all-2023) in Barcelona!

As a supplement, this repository provides working examples of all the functionality covered in the talk. Most of the customizations live inside a helpers module
in `modules/helpers`. If you want to run the site, I've also included a CLI command to generate a small bit of content to help illustrate some of the examples, which you can find in the Installation section.

- [Installation](#installation)
- [#1: Element Actions](#element-actions)
- [#2: Twig Extensions](#twig-extensions)
- [#3: Behaviors](#behaviors)
- [#4: Dashboard Widgets](#dashboard-widgets)
- [#5: Utility Types](#utility-types)
- [#6: Front-End Edit Link](#front-end-edit-link)

* * *

## Installation

Set up Craft

Generate the test content

```bash
php craft helpers/generate-content
```

* * *

## Element Actions

I've created two example actions based on my examples in the talk, and you can find more information about [Element Actions](https://craftcms.com/docs/4.x/extend/element-actions.html) page in the Craft docs.

### Refresh from HRIS

An example “Refresh from HRIS” action is registered on the `User` element and can be triggered by selecting one or more users from the Users index. The external sync logic for
refreshing each user from the external system would live in the `performAction()` method on the action class.

- [modules/helpers/elements/actions/RefreshFromHRIS.php](modules/helpers/elements/actions/RefreshFromHRIS.php)

### Request Content Update

A functional “Request Content Update” action is registered on the `Entry` element and can be triggered by selecting one or more entries from an entries index. The `performAction()`
method on the action class collects the selected entries from the provided `$query` parameter, and queues a `SendEntryUpdateEmail` job for each user. Each job is then responsible
for finding the author of the provided entry ID, and sending the message with links to that entry on the front-end and in the control panel.

- [modules/helpers/elements/actions/RequestContentUpdate.php](modules/helpers/elements/actions/RequestContentUpdate.php)
- [modules/helpers/jobs/SendEntryUpdateEmail.php](modules/helpers/jobs/SendEntryUpdateEmail.php)


* * *

## Twig Extensions

I've included the actual extensions from the two examples I mentioned in the talk, and you can find more information about making Twig Extensions for Craft on the [Extending Twig](https://craftcms.com/docs/4.x/extend/extending-twig.html#create-a-twig-extension) page in the Craft docs, and more information about the other ways you can extend Twig on the [Extending Twig](https://twig.symfony.com/doc/3.x/advanced.html) page of the Twig docs. 

### Hostname Filter

The Hostname Extension provides a single `hostname` filter, which is a passthrough for PHP's `parse_url()` function with the `PHP_URL_HOST` component.

```twig
{{ 'https://craftcms.com/docs' | hostname }} {# craftcms.com #}
```

- [modules/helpers/web/twig/HostnameExtension.php](modules/helpers/web/twig/HostnameExtension.php)

### Icon Extension

The Icon Extension provides a single `icon` function, which accepts either the name of an icon that lives in `resources/icons` or an `Asset`, and an array of HTML attributes to modify on the SVG markup.

```twig
{{ icon('pencil-square', { class: 'w-4 h-4' }) }} 
```

- [modules/helpers/web/twig/IconExtension.php](modules/helpers/web/twig/IconExtension.php)


* * *

## Behaviors

I’ve included a behavior based on the example I used in the talk, and you can find more information on the [Behaviors](https://craftcms.com/docs/4.x/extend/behaviors.html) page in the Craft docs.

### Date Range Behavior

The “Date Range Behavior” is registered only `Entry` class, and limited to only entries in the `events` section. You can see the behavior in use in the [homepage](templates/event.twig) and [event detail page](templates/index.twig) templates. 

- [modules/helpers/behaviors/DateRangeBehavior.php](modules/helpers/behaviors/DateRangeBehavior.php)


* * *

## Dashboard Widgets

I’ve included two example dashboard widgets to demonstrate how widgets can be created, including storing state, and you can find more information on the [Widget Types](https://craftcms.com/docs/4.x/extend/widget-types.html) page in the Craft docs.

### Random Entry Widget

The “Random Entry” widget queries all entries in all sections and surfaces a single random entry from across the site on each page load. The widget class specifies the widget’s name and renders a Twig template for the body. The body template performs the entry query and displays the result.

- [modules/helpers/widgets/RandomEntryWidget.php](modules/helpers/widgets/RandomEntryWidget.php)
- [modules/helpers/templates/widgets/random/body.twig](modules/helpers/templates/widgets/random/body.twig)

### Note to Self Widget

The “Note to Self” widget demonstrates how to store state alongside a widget instance. The widget class has a `$notes` property that is editable by the user. Because widget state is tied to a specific user’s instance of a widget, other users can have their own notes on their own widget. 

The widget body renders the note by accessing it from the widget instance. When the user toggles to the editing view, the widget instance renders the settings template, which uses Craft’s form macros to generate the form input.

- [modules/helpers/widgets/NotesWidget.php](modules/helpers/widgets/NotesWidget.php)
- [modules/helpers/templates/widgets/notes/body.twig](modules/helpers/templates/widgets/notes/body.twig)
- [modules/helpers/templates/widgets/notes/settings.twig](modules/helpers/templates/widgets/notes/settings.twig)


* * *

## Utility Types

I’ve included an example widget similar to an example I mentioned in the talk, and you can find more information on the [Utilities](https://craftcms.com/docs/4.x/extend/utilities.html) page of the Craft docs

### Connection Tester

The “Connection Tester” example shows non-functional UI for an example utility pane that tests the connection to important external services. The utility pane is rendered using a Twig template which outputs the table of services and statuses.

- [modules/helpers/utilities/ConnectionTester.php](modules/helpers/utilities/ConnectionTester.php)
- [modules/helpers/templates/utilities/connection-tester.twig](modules/helpers/templates/utilities/connection-tester.twig)



* * *

## Front-End Edit Link

The bottom of the main layout file includes an example of a front-end edit link. This link uses the entry’s type name in the link text, so the link reads "Edit Article" on articles, and "Edit Event" on events.

- [templates/_layout.twig](templates/_layout.twig)

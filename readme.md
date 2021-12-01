# Sidecar

## Install

`composer require revosystems/sidecar`

> You need to have tailwind and jquery in you main template as `sidecar`

### Configuration
Publish the configuration tot adapt it to you project

`php artisan vendor:publish`

In `config/sidecar.php` you will find the following parameters that can be adapted for your project

PARAMETER          | Default value              | Description
-------------------|----------------------------|-------------
translationsPrefix | admin                      | The prefix it will use for the translations withing the package
routePrefix        | sidecar                    | Sidecar provides some routes (for the widgets, search, etc..) by default it will be `yourproject.com/sidecar/xxx` you can update the prefix here
routeMiddleware    | ['web', 'auth', 'reports'] | The middlewares to use in the the custom sidecar routes (for widgets, search, etc...)
indexLayout		   | admin.reports.layout		| The layout the report view will extend (this one needs to have tailwind and jquery imported)
reportsPath		   | \\App\\Reports\\			| The path where `sidecar` will search for the reports
scripts-stack	   | scripts					| Since `sidecar` does some javascript, it will push it to the scripts stack `https://laravel.com/docs/8.x/blade` you layout needs to have the @stack('scripts') defined
exportRoute			| sidecar.report.export		| When exporting, `sidecar` provides its own route, however if you want to customeize it (for example to use it in a job) you can change the route that will be called

### Global Variables
You can customize some runtime variables implementing the `serving callback`

```
class AppServiceProvider extends ServiceProvider
	
	    public function boot() {
        Sidecar::$usesMultitenant = true;	// When true, all the caches and jobs will use the `auth()->user()->id` as prefix
	    Sidecar::serving(function(){	
	            \Revo\Sidecar\ExportFields\Date::$timezone = auth()->user()->timezone;							// The timezone to display the dates
	            \Revo\Sidecar\ExportFields\Date::$openingTime = auth()->user()->getBusiness()->openingTime;		// To define a day change time instead of 00:00
	            \Revo\Sidecar\ExportFields\Currency::setFormatter('es_ES', auth()->user()->currency ?? 'EUR');	// For the currency field
        });
    }
```		

## Reports

To create your report you should create a new file caled `WathereverYouWantReport` (note it needs to end with Report) in the folder you defined in the `reportsPath` of the config file
This report class needs to extend the main `Revo\Sidecar\Report` class

And define the main model class and implement the fields method

```
<?php

namespace App\Reports;

use Revo\Sidecar\Report;
use App\Post;

class OrdersReport extends Report {

	protected $model  = Post::class;

	public function getFields() : array{
     	   return [ ];
    }
}
```

Now we just need to define the fields we want to show from our report

#### More features

Param          | Description
---------------|-------------
`$title`       | You can customize the title of the report filling this field (or overriding the `getTitle()` function)
`$tooltip`     | You can add a tooltip to explain a bit about the report just filling this field
`$with`        | Even `sidecar` detects automatically the needed withs depending on the export fields, you can also add some extra ones filling this field
`$pagination`  | By default it will paginate for 50 rows to display, you can modify the defaul value for your report
`$exportable`  | Reports are exportable by default, you can set it to false to disable the feature for this report


### Fields

You can define the export fields with a simple array, most of them share the same features

##### Creation
`ExportField::make($field, $title, $dependsOnField)`

Param | Description
------ |------------
$field | The field to show, it will basically do a `data_get($row, $field)` the get the value, so it can contain a dot notation
$title | The title to use in the header / filters / group by for the field
$dependsOnField | There are some cases were the display field, depends on another field, for example a `user.name` would depend on `user_id` on the posts

##### Default options

Function               | Description
-----------------------|-------------
sortable()             | Indicates that the field can be sorted and it will show the sort arrows for it
hideMobile()           | Appends the class `hide-mobile` to the field column
onGroupingBy()         | You can defined how the field works when the report is being grouped by, almost all export fields already provide a default behavior that makes sense but you can override it with this
filterable()           | Makes the field filterable
icon()		           | You can define a font-awesome icon to be displayed instead of the title on the filters list
filterOptions()        | If you don't want the default filter options the `ExportField` provides, you can set your own with this function
groupable()	           | Define if a field is groupable
groupableWithGraph()   | Define if a field is groupable and should display a graph
comparable()           | Define if a field is comparable
onlyWhenGrouping()     | Mark a field to be displayed only when the report is being grouped
tdClasses()			   | Add your own TD Classes that you want to be appended to the column
hidden()			   | To not display the field 
filterOnClick()		   | Some fields can add a link when clicked that filters the report for its value


##### Text
· When filtering it will perform a `like` and you can enter you custom search text
##### Number
· It will align the row to the right
· When grouping by, by default will do a sum (you can change to it with the `onGroupingBy()` funciton)
##### Decimal
· Extends from number
##### Currency
· Extends from number

##### Date
· This field allows different grouping by options (hour, day, dayOfWeek, week, month, quarter)

##### Computed
##### Id
##### BelongsTo
##### BelongsToThrough
##### HasMany
##### HasOne
##### Enum
##### Icon
##### Link

##### Create your ExportField


### Widgets


## Dashboard
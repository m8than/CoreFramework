{{$cur_page}}

{{
//you can put any php here 
//shortcuts for switches/ifs/foreach and for loops work
}}

{{ switch($cur_page) }}
  {{ case "home" }}
    Switch case example
  {{ /case }}
{{ /switch}}

{{ foreach($items as $item) }}

The item is {{ $item }}

{{ /foreach }}

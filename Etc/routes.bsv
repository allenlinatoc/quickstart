{{ Method  | Path | Action }}

GET     | /                       | IndexController@home
GET     | /home                   | IndexController@home
GET     | /login                  | IndexController@login

POST    | /user/authenticate      | UserController@authenticate
POST    | /user/register          | UserController@register
POST    | /user/logout            | UserController@logout
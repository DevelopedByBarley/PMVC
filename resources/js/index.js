import '../scss/main.scss';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap';


import { camelCase } from "lodash";
import { toast } from "./toast.js";
import { validator } from "./validator.js";

validator();
toast();

console.log(camelCase('H'));
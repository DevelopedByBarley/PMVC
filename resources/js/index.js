import '../scss/main.scss';
import '@fortawesome/fontawesome-free/css/all.min.css';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap';



import { camelCase } from "lodash";
import { toast } from "./toast.js";
import { validator } from "./validator.js";
import { theme } from './theme.js';

theme();

validator();
toast();

console.log(camelCase('H'));
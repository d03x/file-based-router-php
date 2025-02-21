import axios from "axios";
import { login } from "./pages/app";

axios.get("http://google.com").then((data) => {
    console.log(data);
})

login();
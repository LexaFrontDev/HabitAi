import {LinkProps} from "react-router-dom";
import {BaseProps} from "./BaseProps";

export type ButtonAsLink = BaseProps & LinkProps & { as: 'link' };
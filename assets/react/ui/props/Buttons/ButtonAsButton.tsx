import React from "react";
import {BaseProps} from "./BaseProps";

export type ButtonAsButton = BaseProps & React.ButtonHTMLAttributes<HTMLButtonElement> & { as?: 'button' };
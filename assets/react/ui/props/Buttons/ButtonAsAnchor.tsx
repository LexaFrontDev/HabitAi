import React from "react";
import {BaseProps} from "./BaseProps";

export type ButtonAsAnchor = BaseProps & React.AnchorHTMLAttributes<HTMLAnchorElement> & { as: 'anchor' };
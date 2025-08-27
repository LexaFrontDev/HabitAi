import {JSX} from "react";

export interface AuthCheckProps {
    isAuthenticated: boolean | null;
    children: JSX.Element;
}

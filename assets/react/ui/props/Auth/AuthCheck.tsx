import React from "react";
import Loading from "../../../pages/chunk/LoadingChunk/Loading";
import {Navigate} from "react-router-dom";
import {AuthCheckProps} from "./AuthCheckProps";


export  const AuthCheck: React.FC<AuthCheckProps> = ({ isAuthenticated, children }) => {
    if (isAuthenticated === null) return <Loading />;
    if (!isAuthenticated) return <Navigate to="/users/login" replace />;

    return children;
};
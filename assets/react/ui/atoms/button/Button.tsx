import React, { useState } from 'react';
import clsx from 'clsx';
import { Link, LinkProps } from 'react-router-dom';
import {ButtonProps} from "../../props/Buttons/ButtonProps";
import {BaseProps} from "../../props/Buttons/BaseProps";
import {ButtonVariants} from "../../props/Buttons/ButtonVariants";

export const Button: React.FC<ButtonProps> = ({
                                                  isActive: propActive = false,
                                                  children,
                                                  className,
                                                  variant = 'primary',
                                                  as = 'button',
                                                  ...props
                                              }) => {
    const internalActive = (props as BaseProps).isActive;
    const onToggle = (props as BaseProps).onToggle;

    const [localActive, setLocalActive] = useState(false);
    const isActive = propActive !== undefined ? propActive : localActive;

    const base = 'px-4 py-2 rounded-xl font-medium transition-colors duration-200';
    const variants: Record<ButtonVariants, string> = {
        primary: 'bg-blue-600 text-white hover:bg-blue-700',
        secondary: 'bg-gray-100 text-gray-800 hover:bg-gray-200',
        danger: 'bg-red-600 text-white hover:bg-red-700',
        login: 'btn btn-outline-secondary me-2',
        register: 'btn btn-outline-secondary me-2',
        big: 'btn btn-lg btn-primary',
        'big-orange': 'btn btn-lg text-white bg-orange-500 hover:bg-orange-600',
        'big-green': 'btn btn-lg text-white bg-green-500 hover:bg-green-600',
        subscribe: 'btn-subscribe bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded-xl fw-semibold',
        listButton: 'triger-list',
        imgButton: 'imgButton',
    };

    const handleClick = (e: React.MouseEvent<HTMLButtonElement>) => {
        if (variant === 'listButton') {
            const nextActive = !isActive;
            setLocalActive(nextActive);
            if (onToggle) onToggle(nextActive);
        }

        if (typeof (props as any).onClick === 'function') {
            (props as any).onClick(e);
        }
    };


    const classes = clsx(
        base,
        variants[variant],
        {
            'active-list-button': variant === 'listButton' && isActive,
        },
        className
    );


    if (as === 'link') {
        return <Link className={classes} {...(props as LinkProps)}>{children}</Link>;
    }

    if (as === 'anchor') {
        return <a className={classes} {...(props as React.AnchorHTMLAttributes<HTMLAnchorElement>)}>{children}</a>;
    }

    return (
        <button
            className={classes}
            onClick={handleClick}
            {...(props as React.ButtonHTMLAttributes<HTMLButtonElement>)}
        >
            {children}
        </button>
    );
};

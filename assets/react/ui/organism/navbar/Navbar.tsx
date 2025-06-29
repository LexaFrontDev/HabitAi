import React from 'react';
import { Link } from 'react-router-dom';

interface NavbarProps {
    logo?: React.ReactNode;
    left?: React.ReactNode;
    right?: React.ReactNode;
    className?: string;
}

const Navbar: React.FC<NavbarProps> = ({ logo, left, right, className }) => (
    <nav className={`navbar shadow-sm border-bottom ${className || ''}`}>
        <div className="container d-flex justify-content-between align-items-center py-2">
            <div className="d-flex align-items-center">
                {logo ?? (
                    <Link className="navbar-logo" to="/">
                        <img src="/Upload/Images/AppIcons/TaskFlowLogo.svg" alt="TaskFlow" />
                    </Link>
                )}
                {left}
            </div>

            <div className="d-flex align-items-center">
                {right}
            </div>
        </div>
    </nav>
);

export default Navbar;

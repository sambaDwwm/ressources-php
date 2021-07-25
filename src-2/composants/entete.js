import React from 'react'
import {
    Link
} from "react-router-dom";

function affNoCo() {
    return (
        <nav>
            <Link to="/">connection</Link>
            <Link to="/inscription">inscription</Link>
        </nav>
    )
}
function affCo() {
    return (
        <nav>
            <Link to="/">Mes taches</Link>
            <Link to="/">Mon Compte</Link>
            <Link to="/">Deconnection</Link>
        </nav>
    )
}
function Entete(props) {
    return (
        <header>
            <h1>Todo List</h1>
            {(props.connect) ? affCo() : affNoCo()}
        </header>
    )
}
export default Entete;
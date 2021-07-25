import React from 'react'
import { Link } from 'react-router-dom'

class Login extends React.Component {
    constructor(props) {
        super(props)
        this.state = { loginVal: "", passVal: "" }
        this.chgLog = this.chgLog.bind(this)
        this.chgPass = this.chgPass.bind(this)
        this.clicConnect = this.clicConnect.bind(this)
    }
    chgLog(e) {
        this.setState({ loginVal: e.target.value })
    }
    chgPass(e) {
        this.setState({ passVal: e.target.value })
    }
    clicConnect() {
        alert("on a cliqué chef !")
    }
    render() {
        return (
            <section id="connexion">
                <article>
                    <input type="text" placeholder="Login" value={this.state.loginVal} onChange={this.chgLog}></input>
                    <input type="password" placeholder="Mot de passe" value={this.state.passVal} onChange={this.chgPass}></input>
                    <button onClick={this.clicConnect}>Connexion</button>
                    <p>Pas de compte, <Link to="/inscription">Inscrivez vous</Link> !</p>
                </article>
            </section>
        )
    }
}
export default Login;
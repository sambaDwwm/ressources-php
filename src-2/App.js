import React from 'react'
import './App.css';
import Entete from './composants/entete';
import Pied from './composants/pied';
import Login from './composants/login';
import Inscription from './composants/inscription';
import {
  BrowserRouter as Router,
  Switch,
  Route
} from "react-router-dom";

class App extends React.Component {
  constructor(props) {
    super(props)
    this.state = { conn: false }
  }
  render() {
    return (
      <Router>
        <Entete connect={this.state.conn} />
        <Switch>
          <Route path="/inscription">
            <Inscription />
          </Route>
          <Route path="/">
            <Login />
          </Route>
        </Switch>
        <Pied />
      </Router>
    );
  }
}

export default App;

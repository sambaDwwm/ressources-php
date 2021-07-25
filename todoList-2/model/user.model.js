const db = require('../connect')
const { v4: uuidv4 } = require('uuid');

class UserModel {
    getAll(cb) {
        db.query("SELECT * FROM utilisateur", (err, result) => {
            cb(err, result)
        })
    }
    getById(id, cb) {
        db.query("SELECT * FROM utilisateur WHERE id_user=?", [id], (err, result) => {
            cb(err, result)
        })
    }
    add(login, mdp, cb) {
        db.query("INSERT INTO utilisateur (login, mdp, cle) VALUES(?,?,?)", [login, mdp, uuidv4()], (err, result) => {
            cb(err, result)
        })
    }
    update(login, mdp, id, cb) {
        db.query("UPDATE utilisateur SET login=?, mdp=?, cle=? WHERE id_user=?", [login, mdp, uuidv4(), id], (err, result) => {
            cb(err, result)
        })
    }
    delete(id, cb) {
        db.query("DELETE FROM utilisateur WHERE id_user=?", [id], (err, result) => {
            cb(err, result)
        })
    }
}

let userM = new UserModel()
module.exports = userM
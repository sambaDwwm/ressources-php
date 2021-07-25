const db = require('../connect')

class TaskModel {
    getAll(cb) {
        db.query("SELECT * FROM tache", (err, result) => {
            cb(err, result)
        })
    }
    getById(id, cb) {
        db.query("SELECT * FROM tache WHERE id=?", [id], (err, result) => {
            cb(err, result)
        })
    }
    add(titre, desc, idUser, cb) {
        db.query("INSERT INTO tache (titre, description, statut,id_user) VALUES(?,?,?,?)", [titre, desc, 0, idUser], (err, result) => {
            cb(err, result)
        })
    }
    update(titre, desc, statut, idUser, id, cb) {
        db.query("UPDATE tache SET titre=?, description=?, statut=?, id_user=? WHERE id=?", [titre, desc, statut, idUser, id], (err, result) => {
            cb(err, result)
        })
    }
    delete(id, cb) {
        db.query("DELETE FROM tache WHERE id=?", [id], (err, result) => {
            cb(err, result)
        })
    }
}

let taskM = new TaskModel()
module.exports = taskM
const taskM = require("../model/task.model")
const express = require('express')
const auth = require("../middleware/auth")

let router = express.Router()

router.get('/', auth, (req, res) => {
    taskM.getAll((err, result) => {
        if (err) res.status(500).json(err)
        else res.status(200).json(result)
    })
})
router.get('/:id', auth, (req, res) => {
    taskM.getById(req.params.id, (err, result) => {
        if (err) res.status(500).json(err)
        else res.status(200).json(result)
    })
})
router.post('/', auth, (req, res) => {
    taskM.add(req.body.titre, req.body.description, req.idUser, (err, result) => {
        if (err) res.status(500).json(err)
        else res.status(200).json(result)
    })
})
router.put('/:id', auth, (req, res) => {
    taskM.update(req.body.titre, req.body.description, req.body.statut, req.body.idUser, req.params.id, (err, result) => {
        if (err) res.status(500).json(err)
        else res.status(200).json(result)
    })
})
router.delete('/:id', auth, (req, res) => {
    taskM.delete(req.params.id, (err, result) => {
        if (err) res.status(500).json(err)
        else res.status(200).json(result)
    })
})
module.exports = router
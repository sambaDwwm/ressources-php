const userM = require("../model/user.model")
const express = require('express')

let router = express.Router()

router.get('/', (req, res) => {
    userM.getAll((err, result) => {
        if (err) res.status(500).json(err)
        else res.status(200).json(result)
    })
})
router.get('/:id', (req, res) => {
    userM.getById(req.params.id, (err, result) => {
        if (err) res.status(500).json(err)
        else res.status(200).json(result)
    })
})
router.post('/', (req, res) => {
    userM.add(req.body.login, req.body.mdp, (err, result) => {
        if (err) res.status(500).json(err)
        else res.status(200).json(result)
    })
})
router.put('/:id', (req, res) => {
    userM.update(req.body.login, req.body.mdp, req.params.id, (err, result) => {
        if (err) res.status(500).json(err)
        else res.status(200).json(result)
    })
})
router.delete('/:id', (req, res) => {
    userM.delete(req.params.id, (err, result) => {
        if (err) res.status(500).json(err)
        else res.status(200).json(result)
    })
})
module.exports = router
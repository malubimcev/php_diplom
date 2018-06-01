<?php

    interface ModelInterface
    {
        public function add($data);
        public function update($id, $data);
        public function delete($id);
        public function getList();
        public function getById($id);
    }


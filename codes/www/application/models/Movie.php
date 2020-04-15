<?php

class Movie extends CI_Model {
    //https://api.themoviedb.org/3/discover/movie?api_key=45d065f5f7d98d17d694d53f85abd907&language=en-US&sort_by=popularity.desc&include_adult=false&include_video=false&page=1
    //https://api.themoviedb.org/3/movie/495764/videos?api_key=45d065f5f7d98d17d694d53f85abd907&language=en-US
    //https://api.themoviedb.org/3/movie/38700/reviews?api_key=45d065f5f7d98d17d694d53f85abd907&language=en-US&page=1

    private $table = "movies";

    public function Movie()
    {
        parent::__construct();
        $this->load->database();
    }

    private function genMovies() {
        $url = "https://api.themoviedb.org/3/discover/movie?api_key=45d065f5f7d98d17d694d53f85abd907&language=en-US&sort_by=popularity.desc&include_adult=false&include_video=false&page=".rand(1,500);
        $data = file_get_contents($url);
        $data = json_decode($data, true);
        return $data["results"];
    }

    public function shuffleMovies($groupId) {
        foreach ($this->genMovies() as $v) {
            $data = array(
                'group_id' => $groupId,
                'movie_object' => json_encode($v),
                'movie_object_hash' => md5(json_encode($v)),
            );
            $this->db->insert($this->table, $data);
        }
    }

    public function getGroupMovies($groupId) {
        $data = $this->db->where(array('group_id'=>$groupId))->order_by('id', 'DESC')->get($this->table)->result_array();
        $ret = [];
        foreach ($data as $k=>$v) {
            $ret[$k] = json_decode($v['movie_object'], true);
            $ret[$k]['mid'] = $v['id'];
        }
        return $ret;
    }

    public function getMovie($mid) {
        $data = $this->db->where(array('id'=>$mid))->get($this->table)->result_array();
        $data = json_decode($data[0]['movie_object'], true);
        $video = file_get_contents("https://api.themoviedb.org/3/movie/".$data['id']."/videos?api_key=45d065f5f7d98d17d694d53f85abd907&language=en-US");
        //echo "https://api.themoviedb.org/3/movie/".$data['id']."/videos?api_key=45d065f5f7d98d17d694d53f85abd907&language=en-US";
        $video = json_decode($video,true);
        //var_dump($video);
        if(count($video['results']) > 0) {

            $data['youtube'] = $video['results'][0]['key'];
        } else {

            $data['youtube'] = "";
        }
        $reviews = file_get_contents("https://api.themoviedb.org/3/movie/".$data['id']."/reviews?api_key=45d065f5f7d98d17d694d53f85abd907&language=en-US");
        $reviews = json_decode($reviews,true);
        $data['reviews'] = $reviews['results'];
        return $data;
    }

    public function deleteMovies($groupId) {
        $where = array(
            'group_id' => $groupId,
        );
        try {
            $this->db->where($where)->delete($this->table);
        } catch (Exception $e) {
            throw $e;
        }
    }


}
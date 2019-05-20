<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Components\QueryBuilderComponent;

class PostsTest extends TestCase
{
    protected $sql;
    public function setUp(): void
    {
       $this->sql = new QueryBuilderComponent;
    }

    public function testSelectAll()
    {
        $this->assertEquals('select * from posts', $this->sql->select('posts'));
    }

    public function testColumns()
    {
        $this->assertEquals('select id, post_subject from posts', $this->sql->select('posts', ['id', 'post_subject']));
    }

    public function testColumnOrder()
    {
        $this->assertEquals('select id, post_subject from posts order by id desc', $this->sql->select('posts', ['id', 'post_subject'], ['id', 'desc']));
    }

    public function testColumnSorting()
    {
        $this->assertEquals('select * from posts order by post_subject asc, category asc', $this->sql->select('posts', [['post_subject', 'asc'],['category','asc']]));
    }

    public function testCasing()
    {
        $this->assertEquals('SELECT id, post_subject FROM posts ORDER BY id DESC', $this->sql->select('posts', ['id', 'post_subject'], ['id', 'DESC']));
    }

    public function testLimit()
    {
        $this->assertEquals('select * from posts limit 10', $this->sql->select('posts', 10));
    }

    public function testOffset()
    {
        $this->assertEquals('select * from posts limit 6 offset 5', $this->sql->select('posts', [6, 5]));
    }

    public function testCount()
    {
        $this->assertEquals('select *, count("id") from posts', $this->sql->select('posts', ['count','id']));
    }

    public function testMax()
    {
        $this->assertEquals('select max(\'comment_count\') from posts', $this->sql->select('posts', ['max','comment_count']));
    }

    public function testGroupBy()
    {
        $this->assertEquals('select max(\'comment_count\') from posts group by comment_count', $this->sql->select('posts', ['group by','comment_count']));
    }

    public function testDistinct()
    {
        $this->assertEquals('select distinct post_subject from posts', $this->sql->select('posts', ['distinct','post_subject']));
    }

    public function testJoin()
    {
        $this->assertEquals('select * from posts join users on posts.user_id=users.id', $this->sql->selectJoin('posts', 'users', ['user_id', 'id']));
    }

    public function testInsert()
    {
        $this->assertEquals('INSERT INTO posts(id, name, comment_count, color) VALUES(1, "apple", 100, "red")', $this->sql->insert('posts', ["id","name","comment_count","color"], [[1, "apple", 100, "red"]]));
    }

    public function testInsertMultiple()
    {
        $this->assertEquals('INSERT INTO posts(id, post_subject, comment_count, color) VALUES(1, "apple", 100, "red"), (2, "orange", 50, "orange")', $this->sql->insert('posts', ["id","post_subject","comment_count","color"], [[1, "apple", 100, "red"],[2, "orange", 50, "orange"]] ));
    }

    public function testInsertWithDefaut()
    {
        $this->assertEquals('INSERT INTO posts(id, post_subject, comment_count, color) VALUES(1, "apple", 100, "DEFAULT")', $this->sql->insert('posts', ["id","post_subject","comment_count","color"], [[1, "apple", 100, 'DEFAULT']]));
    }

    public function testUpdateCost(){
        $this->assertEquals('UPDATE posts SET comment_count=200 WHERE name = "apple"', $this->sql->update('posts', ["comment_count",200], ["name", "apple"]));
    }

    public function testUpdateColor(){
        $this->assertEquals('UPDATE posts SET color="black" WHERE color = "red"', $this->sql->update('posts', ["color", "black"], ["color", "red"]));
    }

    public function testUpdateCostDefault(){
        $this->assertEquals('UPDATE posts SET comment_count=DEFAULT WHERE comment_count = 100', $this->sql->update('posts', ["comment_count", 'DEFAULT'], ["comment_count", 100]));
    }

    public function testDeleteEqual(){
        $this->assertEquals('DELETE FROM posts WHERE name="abc"', $this->sql->delete('posts', ["name", "abc"]));
    }

    public function testDeletegreater(){
        $this->assertEquals('DELETE FROM posts WHERE comment_count>500', $this->sql->delete('posts', ["comment_count", ">", 100]));
    }

    public function testDelete(){
        $this->assertEquals('DELETE FROM posts', $this->sql->delete('posts'));
    }

}
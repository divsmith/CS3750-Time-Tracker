<?php
/**
 * Created by PhpStorm.
 * User: kidlappy
 * Date: 10/18/16
 * Time: 11:41 PM
 */

use RoadHome\Domain\StringLiteral;

describe('Project1\Domain\StringLiteral', function () {
    describe('->__construct("test string")', function () {
        it('should return a StringLiteral object', function () {
            $actual = new StringLiteral('test string');
            expect($actual)->to->be->instanceof('Project1\Domain\StringLiteral');
        });
        it('should return the correct string value', function () {
            $actual = new StringLiteral('test string');
            expect($actual->toNative())->equal('test string');
        });
    });
    describe('->__construct(13)', function () {
        it('should return an InvalidArgumentException object', function () {
            $e = null;
            try {
                new StringLiteral(13);
            } catch (Exception $ex) {
                $e = $ex;
            }
            expect($e)->to->be->instanceof('InvalidArgumentException');
            expect($e->getMessage())->to->equal(
                'Project1\Domain\StringLiteral: only accepts strings, integer passed'
            );
        });
    });
    describe('->__toString()', function () {
        it('should return the string "foo"', function () {
            $string = new StringLiteral('foo');
            expect((string)$string)->equal('foo');
        });
    });
    describe('->equal()', function () {
        it('should return the correct boolean value', function () {
            $foo1 = new StringLiteral('foo');
            $foo2 = new StringLiteral('foo');
            $bar = new StringLiteral('bar');
            expect($foo1->equal($foo2))->true();
            expect($foo2->equal($foo1))->true();
            expect($foo1->equal($bar))->false();
        });
    });
    describe('->fromNative()', function () {
        it('should return boolean true', function () {
            $string = StringLiteral::fromNative('foo');
            $constructedString = new StringLiteral('foo');
            expect($string->equal($constructedString))->true();
        });
    });
    describe('->isEmpty()', function () {
        it('should return boolean true', function () {
            $string = new StringLiteral('');
            expect($string->isEmpty())->true();
        });
    });
    describe('->toNative()', function () {
        it('should return boolean true', function () {
            $string = new StringLiteral('foo');
            expect($string->toNative())->equal('foo');
        });
    });
    describe('->jsonSerialize()', function () {
        it('should return a string for json_encode()', function () {
            $string = new StringLiteral('foo');
            expect($string->jsonSerialize())->equal('foo');
            expect(json_encode($string))->equal(json_encode('foo'));
        }) ;
    });
});


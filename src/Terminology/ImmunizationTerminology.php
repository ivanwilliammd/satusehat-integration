<?php

declare(strict_types=1);

namespace Satusehat\Integration\Terminology;

/**
 * Immunization terminology — KFA vaccine code mappings for SATUSEHAT.
 *
 * @link https://www.hl7.org/fhir/R4/immunization.html
 * @link https://farmalkes.kemkes.go.id/kfa
 */
class ImmunizationTerminology
{
    /** @var array<string, array<int, array{system: string, code: string, display: string}>> */
    public array $vaccine_map = [
        '93001282' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93001282', 'display' => 'Vaksin DTP - HB - Hib 0,5 mL (PENTABIO, 1)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG17', 'display' => 'HIB'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG45', 'display' => 'HepB'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG107', 'display' => 'DTAP'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '198', 'display' => 'DTP-hepB-Hib Pentavalent Non-US'],
        ],
        '93003730' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93003730', 'display' => 'Vaksin Hepatitis B Recombinant 20 ug/1 mL Suspensi Injeksi (Umum)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG45', 'display' => 'HepB'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '43', 'display' => 'Hep B, adult'],
        ],
        '93005477' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93005477', 'display' => 'Vaksin Hepatitis B Recombinant 20 ug/1 mL Suspensi Injeksi (BIO FARMA, 1)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG45', 'display' => 'HepB'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '43', 'display' => 'Hep B, adult'],
        ],
        '93005208' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93005208', 'display' => 'Vaksin BCG Kering 0.375 mg/mL Serbuk Injeksi (BIO FARMA, 20)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG19', 'display' => 'BCG'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '19', 'display' => 'BCG'],
        ],
        '93004566' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93004566', 'display' => 'Vaksin BCG Kering 0.75 mg Serbuk Injeksi (AJ Vaccine, 10)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG19', 'display' => 'BCG'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '19', 'display' => 'BCG'],
        ],
        '93003837' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93003837', 'display' => 'Vaksin BCG Kering 1x10^5 - 4x10^5 C.F.U 0.05 mL Serbuk Injeksi (BIO FARMA, 20)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG19', 'display' => 'BCG'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '19', 'display' => 'BCG'],
        ],
        '93001283' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93001283', 'display' => 'Vaksin DTP - HB - Hib 0,5 mL (PENTABIO, 5)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG45', 'display' => 'HepB'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG17', 'display' => 'HIB'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG107', 'display' => 'DTAP'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '198', 'display' => 'DTP-hepB-Hib Pentavalent Non-US'],
        ],
        '93001284' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93001284', 'display' => 'Vaksin DTP - HB - Hib 0,5 mL (PENTABIO, 10)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG45', 'display' => 'HepB'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG17', 'display' => 'HIB'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG107', 'display' => 'DTAP'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '198', 'display' => 'DTP-hepB-Hib Pentavalent Non-US'],
        ],
        '93004972' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93004972', 'display' => 'Vaksin Poliomyelitis Oral Bivalent Tipe 1 10^6 CCID_50 / Tipe 3 10^5,8 CCID_50 0,1 mL (BIO FARMA, 10)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG89', 'display' => 'POLIO'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '178', 'display' => 'OPV bivalent'],
        ],
        '93004968' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93004968', 'display' => 'Vaksin Poliomyelitis Oral Bivalent Tipe 1 10^6 CCID_50 / Tipe 3 10^5,8 CCID_50 0,1 mL (BIO FARMA, 20)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG89', 'display' => 'POLIO'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '178', 'display' => 'OPV bivalent'],
        ],
        '93004891' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93004891', 'display' => 'Vaksin Poliomyelitis Oral Monovalen Tipe 1 (BIO FARMA, 20)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG89', 'display' => 'POLIO'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '179', 'display' => 'OPV ,monovalent, unspecified'],
        ],
        '93004996' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93004996', 'display' => 'Vaksin Poliomyelitis Oral Monovalen Tipe 2 10^5.0 CCID_50/0.1 mL (BIO FARMA, 20)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG89', 'display' => 'POLIO'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '179', 'display' => 'OPV ,monovalent, unspecified'],
        ],
        '93005778' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93005778', 'display' => 'Vaksin Poliomyelitis Oral Monovalen Tipe 2 10^5.0 CCID_50/0.1 mL (BIO FARMA, 50)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG89', 'display' => 'POLIO'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '179', 'display' => 'OPV ,monovalent, unspecified'],
        ],
        '93004980' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93004980', 'display' => 'Vaksin Poliomyelitis Oral Trivalen 0,5 mL (BIO FARMA, 10)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG89', 'display' => 'POLIO'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '2', 'display' => 'OPV'],
        ],
        '93004983' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93004983', 'display' => 'Vaksin Poliomyelitis Oral Trivalen 0,5 mL (BIO FARMA, 20)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG89', 'display' => 'POLIO'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '2', 'display' => 'OPV'],
        ],
        '93004736' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93004736', 'display' => 'Vaksin Poliomyelitis Inaktif (IPV) 0,5 mL (Shan IPV, 10)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG89', 'display' => 'POLIO'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '10', 'display' => 'IPV'],
        ],
        '93004947' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93004947', 'display' => 'Vaksin Poliomyelitis Inaktif (IPV) 0,5 mL (Shan IPV, 5)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG89', 'display' => 'POLIO'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '10', 'display' => 'IPV'],
        ],
        '93005779' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93005779', 'display' => 'Vaksin M/R 1000 CCID_50, 0.5 mL (BIO FARMA, 1)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG03', 'display' => 'MMR'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '4', 'display' => 'M/R'],
        ],
        '93005781' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93005781', 'display' => 'Vaksin M/R 1000 CCID_50, 0.5 mL (BIO FARMA, 5)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG03', 'display' => 'MMR'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '4', 'display' => 'M/R'],
        ],
        '93005780' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93005780', 'display' => 'Vaksin M/R 1000 CCID_50, 0.5 mL (BIO FARMA, 10)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG03', 'display' => 'MMR'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '4', 'display' => 'M/R'],
        ],
        '93001619' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93001619', 'display' => 'Vaksin Streptococcus Pneumoniae Serotype 0,5mL (PNEUMOSIL, 1)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG152', 'display' => 'PneumoPCV'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '152', 'display' => 'Pneumococcal Conjugate, unspecified formulation'],
        ],
        '93001620' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93001620', 'display' => 'Vaksin Streptococcus Pneumoniae Serotype 0,5mL (PNEUMOSIL, 5)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG152', 'display' => 'PneumoPCV'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '152', 'display' => 'Pneumococcal Conjugate, unspecified formulation'],
        ],
        '93001652' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93001652', 'display' => 'Vaksin Rotavirus Live Attenuated, G1P[8] Human 89-12 Strain <106 CCID50 1,5 mL (ROTARIX, 1)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG122', 'display' => 'ROTAVIRUS'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '119', 'display' => 'rotavirus, monovalent'],
        ],
        '93001653' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93001653', 'display' => 'Vaksin Rotavirus Pentavalent 2 mL (ROTATEQ, 1)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG122', 'display' => 'ROTAVIRUS'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '116', 'display' => 'rotavirus, pentavalent'],
        ],
        '93001639' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93001639', 'display' => 'Vaksin JE 0,5 mL (BIO FARMA, 5)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG129', 'display' => 'Japanese encephalitis'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '134', 'display' => 'Japanese Encephalitis IM'],
        ],
        '93001642' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93001642', 'display' => 'Vaksin Diphteri Toxoid 20 Lf / Tetanus toxoid 7,5 Lf 0,5 mL (BIO FARMA, 10)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG107', 'display' => 'DTAP'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '28', 'display' => 'DT (pediatric)'],
        ],
        '93001640' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93001640', 'display' => 'Vaksin Diphteri Toxoid 2 Lf / Tetanus toxoid 7,5 Lf 0,5 mL (BIOFARMA, 1)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG107', 'display' => 'DTAP'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '28', 'display' => 'DT (pediatric)'],
        ],
        '93001641' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93001641', 'display' => 'Vaksin Diphteri Toxoid 2 Lf / Tetanus toxoid 7,5 Lf 0,5 mL (BIOFARMA, 10)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG107', 'display' => 'DTAP'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '28', 'display' => 'DT (pediatric)'],
        ],
        '93006988' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93006988', 'display' => 'Vaksin Diphteri Toxoid 2 Lf / Tetanus toxoid 7,5 Lf/0,5 mL Injeksi (BIO FARMA, 10)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG107', 'display' => 'DTAP'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '139', 'display' => 'Td'],
        ],
        '93006989' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93006989', 'display' => 'Vaksin Diphteri Toxoid 2 Lf / Tetanus toxoid 7,5 Lf/0,5 mL Injeksi (BIO FARMA)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG107', 'display' => 'DTAP'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '139', 'display' => 'Td'],
        ],
        '93014731' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93014731', 'display' => 'Vaksin Diphteri Toxoid 2 Lf / Tetanus toxoid 7,5 Lf/0,5 mL Injeksi (Bio Td, 1)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG107', 'display' => 'DTAP'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '139', 'display' => 'Td'],
        ],
        '93001623' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93001623', 'display' => 'Vaksin HPV quadrivalent 0,5 mL (GARDASIL, 1)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG137', 'display' => 'HPV'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '62', 'display' => 'HPV, quadrivalent'],
        ],
        '93001278' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93001278', 'display' => 'Vaksin Meningococcal Polysaccharide 50 ug 0,5 mL (MENIVAX ACYW)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG108', 'display' => 'MENING'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '108', 'display' => 'meningococcal ACWY, unspecified formulation'],
        ],
        '93001279' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93001279', 'display' => 'Vaksin Meningococcal Polysaccharide 50 ug 0,5 mL (FORMENING)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG108', 'display' => 'MENING'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '108', 'display' => 'meningococcal ACWY, unspecified formulation'],
        ],
        '93001560' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93001560', 'display' => 'Vaksin Yellow Fever 1000 IU 0,5 mL (STAMARIL PASTEUR, 1)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG184', 'display' => 'MENING'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '183', 'display' => 'Yellow fever vaccine - alt'],
        ],
        '93001589' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93001589', 'display' => 'Vaksin Rabies (Wistar PM/WI 38-1503-3M Strain) 2,5 IU 0,5 mL (VERORAB)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG90', 'display' => 'RABIES'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '18', 'display' => 'rabies, intramuscular injection'],
        ],
        '93003763' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93003763', 'display' => 'Vaksin Rabies Inactivated rabies virus (flury lep) NLT 2.5 IU/mL (CHIRORAB)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG90', 'display' => 'RABIES'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '18', 'display' => 'rabies, intramuscular injection'],
        ],
        '93002539' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93002539', 'display' => 'Vaksin Varicella 3.3 Log PFU 0.5 mL Serbuk Injeksi (Varicella Vaccine, Live)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG21', 'display' => 'VARICELLA'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '21', 'display' => 'varicella'],
        ],
        '93004691' => [
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => '93004691', 'display' => 'Vaksin Hepatitis A 160 IU/0.5 mL Suspensi Injeksi (AVAXIM, 1)'],
            ['system' => 'http://sys-ids.kemkes.go.id/kfa', 'code' => 'VG85', 'display' => 'HepA'],
            ['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '52', 'display' => 'Hep A, adult'],
        ],
    ];
}
